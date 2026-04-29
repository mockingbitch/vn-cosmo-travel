<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class GuideController extends Controller
{
    public function __invoke(): View
    {
        $path = base_path('docs/HUONG_DAN_ADMIN.md');

        abort_unless(File::isFile($path), 404);

        $converter = new GithubFlavoredMarkdownConverter([
            'allow_unsafe_links' => false,
            'html_input' => 'strip',
        ]);

        $guideHtml = $converter->convert(File::get($path))->getContent();

        return view('admin.guide', [
            'title' => __('admin.guide.title'),
            'guideHtml' => $guideHtml,
        ]);
    }
}
