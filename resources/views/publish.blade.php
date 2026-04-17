<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>发布到公共主页</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        .success { color: green; margin-bottom: 15px; }
        .error { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
<h1>发布内容到 Facebook 公共主页</h1>

@if(session('success'))
    <div class="success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="error">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="error">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('facebook.page.publish') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>选择公共主页:</label><br>
        <select name="page_id" required>
            <option value="">请选择你要发帖的主页</option>
            @foreach($userPages as $page)
                <option value="{{ $page['id'] }}">
                    {{ $page['name'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>帖子内容:</label><br>
        <textarea name="message" rows="5" style="width: 300px; padding: 5px;" required placeholder="写点什么..."></textarea>
    </div>

    <button type="submit" style="padding: 10px 20px; background-color: #1877f2; color: white; border: none; cursor: pointer;">
        立即发布
    </button>
</form>

</body>
</html>
