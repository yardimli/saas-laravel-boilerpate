<h1 class='blog_title'>{{$post->title}}</h1>
<h5 class='blog_subtitle'>{{$post->subtitle}}</h5>


<?=$post->image_tag("medium", false, 'd-block mx-auto'); ?>

<p class="blog_body_content">
    {!! $post->post_body_output() !!}
</p>

<hr/>

Posted <strong>{{$post->post->posted_at->diffForHumans()}}</strong>

@includeWhen($post->author,"binshopsblog::partials.author",['post'=>$post])
@includeWhen($categories,"binshopsblog::partials.categories",['categories'=>$categories])
