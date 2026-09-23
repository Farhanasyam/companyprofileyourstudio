<?php

namespace App\Helpers;

class HtmlSanitizer
{
    /**
     * Keep only the formatting tags used by the article editor and remove all attributes.
     */
    public static function clean(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        $allowedTags = ['p', 'br', 'strong', 'em', 'b', 'i', 'u', 'ul', 'ol', 'li', 'h2', 'h3', 'h4', 'blockquote', 'pre', 'code'];
        $allowedTagString = '<' . implode('><', $allowedTags) . '>';
        $clean = strip_tags(html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8'), $allowedTagString);

        return preg_replace_callback(
            '/<\/?([a-z0-9]+)(?:\s[^>]*)?>/i',
            static function (array $matches) use ($allowedTags): string {
                $tag = strtolower($matches[1]);

                if (!in_array($tag, $allowedTags, true)) {
                    return '';
                }

                return str_starts_with($matches[0], '</') ? '</' . $tag . '>' : '<' . $tag . '>';
            },
            $clean
        ) ?? '';
    }

    /**
     * Sanitizer untuk konten TinyMCE yang lebih kaya (deskripsi produk): link, gambar, tabel,
     * dan perataan teks tetap ada, tetapi script, event handler (on*), dan URL javascript: dibuang.
     */
    public static function cleanRich(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        $allowed = [
            'p' => [], 'br' => [], 'hr' => [], 'span' => [], 'div' => [],
            'strong' => [], 'b' => [], 'em' => [], 'i' => [], 'u' => [], 's' => [], 'sub' => [], 'sup' => [],
            'h1' => [], 'h2' => [], 'h3' => [], 'h4' => [], 'h5' => [], 'h6' => [],
            'ul' => [], 'ol' => [], 'li' => [], 'blockquote' => [], 'pre' => [], 'code' => [],
            'a' => ['href', 'title', 'target'],
            'img' => ['src', 'alt', 'title', 'width', 'height'],
            'table' => [], 'thead' => [], 'tbody' => [], 'tfoot' => [], 'tr' => [], 'caption' => [],
            'th' => ['colspan', 'rowspan'], 'td' => ['colspan', 'rowspan'],
        ];
        // Elemen yang dibuang beserta seluruh isinya
        $dropWithContent = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'svg', 'math', 'template', 'noscript'];

        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $doc = new \DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8"><div id="__root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $doc->getElementById('__root');
        if (!$root) {
            return '';
        }

        self::sanitizeNode($root, $allowed, $dropWithContent);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }

        return $out;
    }

    private static function sanitizeNode(\DOMNode $node, array $allowed, array $dropWithContent): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof \DOMComment) {
                $node->removeChild($child);
                continue;
            }
            if (!$child instanceof \DOMElement) {
                continue;
            }

            $tag = strtolower($child->nodeName);

            if (in_array($tag, $dropWithContent, true)) {
                $node->removeChild($child);
                continue;
            }

            self::sanitizeNode($child, $allowed, $dropWithContent);

            if (!array_key_exists($tag, $allowed)) {
                // Tag tidak dikenal: buang tag-nya, pertahankan isinya
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            foreach (iterator_to_array($child->attributes) as $attr) {
                $name = strtolower($attr->name);
                $value = trim($attr->value);

                if ($name === 'style') {
                    $style = self::cleanStyle($value);
                    $style === '' ? $child->removeAttribute($attr->name) : $child->setAttribute('style', $style);
                    continue;
                }
                if (!in_array($name, $allowed[$tag], true)) {
                    $child->removeAttribute($attr->name);
                    continue;
                }
                if (in_array($name, ['href', 'src'], true) && !self::isSafeUrl($value, $name === 'href')) {
                    $child->removeAttribute($attr->name);
                }
            }

            if ($tag === 'a' && $child->getAttribute('target') === '_blank') {
                $child->setAttribute('rel', 'noopener noreferrer');
            } elseif ($tag === 'a') {
                $child->removeAttribute('target');
            }
        }
    }

    private static function isSafeUrl(string $url, bool $allowMailTel): bool
    {
        // Hilangkan karakter kontrol/spasi yang bisa dipakai untuk menyamarkan "javascript:"
        $normalized = strtolower(preg_replace('/[\x00-\x20]+/', '', $url) ?? '');

        if ($normalized === '' || str_starts_with($normalized, '/') || str_starts_with($normalized, '#')) {
            return true;
        }

        $schemes = $allowMailTel ? ['http:', 'https:', 'mailto:', 'tel:'] : ['http:', 'https:'];
        foreach ($schemes as $scheme) {
            if (str_starts_with($normalized, $scheme)) {
                return true;
            }
        }

        // URL relatif tanpa skema (mis. "storage/foo.jpg") diizinkan
        return !str_contains(strtok($normalized, '/?#') ?: '', ':');
    }

    private static function cleanStyle(string $style): string
    {
        $allowedProps = ['text-align', 'color', 'background-color', 'font-weight', 'font-style', 'text-decoration', 'width', 'height'];
        $kept = [];

        foreach (explode(';', $style) as $decl) {
            [$prop, $val] = array_pad(array_map('trim', explode(':', $decl, 2)), 2, '');
            $prop = strtolower($prop);
            if ($prop === '' || $val === '' || !in_array($prop, $allowedProps, true)) {
                continue;
            }
            if (!preg_match('/^[#a-z0-9\s.,%()-]+$/i', $val) || preg_match('/url|expression/i', $val)) {
                continue;
            }
            $kept[] = $prop . ': ' . $val;
        }

        return implode('; ', $kept);
    }
}