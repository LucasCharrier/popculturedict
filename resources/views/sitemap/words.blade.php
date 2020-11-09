<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($words as $word)
        <url>
            <loc><?php echo env('APP_FRONT_URL') ? env('APP_FRONT_URL') : 'https://lepopdictionnaire.herokuapp.com'; ?>/?q={{ $word->name }}</loc>
            <lastmod>{{ $word->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
</urlset>