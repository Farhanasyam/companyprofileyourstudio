<?php

namespace Tests\Unit;

use App\Helpers\HtmlSanitizer;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_rich_keeps_editor_formatting(): void
    {
        $html = '<p style="text-align: center;">Halo <strong>dunia</strong> <a href="https://example.com" target="_blank">link</a></p>'
            . '<img src="/storage/products/a.jpg" alt="Kuas"><table><tbody><tr><td colspan="2">Sel</td></tr></tbody></table>';

        $out = HtmlSanitizer::cleanRich($html);

        $this->assertStringContainsString('style="text-align: center"', $out);
        $this->assertStringContainsString('<strong>dunia</strong>', $out);
        $this->assertStringContainsString('href="https://example.com"', $out);
        $this->assertStringContainsString('rel="noopener noreferrer"', $out);
        $this->assertStringContainsString('src="/storage/products/a.jpg"', $out);
        $this->assertStringContainsString('colspan="2"', $out);
    }

    public function test_rich_removes_scripts_and_handlers(): void
    {
        $payloads = [
            '<script>alert(1)</script>',
            '<img src="x" onerror="alert(1)">',
            '<a href="javascript:alert(1)">x</a>',
            '<a href="  java&#x09;script:alert(1)">x</a>',
            '<iframe src="https://evil.test"></iframe>',
            '<svg onload="alert(1)"></svg>',
            '<p style="background:url(javascript:alert(1))">x</p>',
            '&lt;script&gt;alert(1)&lt;/script&gt;',
        ];

        foreach ($payloads as $payload) {
            $out = strtolower(HtmlSanitizer::cleanRich($payload));
            $this->assertStringNotContainsString('<script', $out, $payload);
            $this->assertStringNotContainsString('onerror', $out, $payload);
            $this->assertStringNotContainsString('onload', $out, $payload);
            $this->assertStringNotContainsString('javascript', $out, $payload);
            $this->assertStringNotContainsString('<iframe', $out, $payload);
        }
    }

    public function test_rich_handles_utf8_and_empty(): void
    {
        $this->assertSame('', HtmlSanitizer::cleanRich(null));
        $this->assertSame('', HtmlSanitizer::cleanRich('   '));
        $this->assertSame('<p>Cat akrilik — warna cerah</p>', HtmlSanitizer::cleanRich('<p>Cat akrilik — warna cerah</p>'));
    }
}
