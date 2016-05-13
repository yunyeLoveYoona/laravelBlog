<html>
<head>
<title>{{ config('news.title') }}</title>
<link
	href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"
	rel="stylesheet">
</head>
<body>
	<div class="container">
		<h1>{{ config('news.title') }}</h1>
		<h5>Page {{ $news->currentPage() }} of {{ $news->lastPage() }}</h5>
		<hr>
		<ul>
			@foreach ($news as $new)
			<li><a href="./news/{{ $new->id }}">{{ $new->title }}</a> <em>({{
					$new->created_at }})</em>
				<p>{{ str_limit($new->content) }}</p></li> @endforeach
		</ul>
		<hr>
		{!! $news->render() !!}
	</div>
</body>
</html>