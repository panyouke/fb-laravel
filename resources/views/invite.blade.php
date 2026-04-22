<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>邀请成员到商务平台</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 40px; background-color: #f0f2f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,.1); }
        h1 { font-size: 24px; color: #1c1e21; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; color: #4b4f56; }
        select, input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 16px; }
        .success { padding: 15px; background-color: #e7f3ff; color: #1877f2; border-radius: 6px; margin-bottom: 20px; border: 1px solid #1877f2; }
        .error { padding: 15px; background-color: #ffebe8; color: #f02849; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f02849; }
        .btn-submit { width: 100%; padding: 12px; background-color: #1877f2; color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background-color: #166fe5; }
        .hint { font-size: 12px; color: #90949c; margin-top: 5px; }
    </style>
</head>
<body>

<div class="container">
    <h1>邀请成员到 BM 平台</h1>

    {{-- 成功提示 --}}
    @if(session('success'))
        <div class="success">✅ {{ session('success') }}</div>
    @endif

    {{-- 错误提示 --}}
    @if(session('error'))
        <div class="error">❌ {{ session('error') }}</div>
    @endif

    <form action="{{ route('facebook.page.processInvite') }}" method="POST">
        @csrf

        {{-- 选择 BM 的下拉框，注意 name 要对应上 --}}
        <select name="bm_internal_id" required>
            @foreach($bms as $bm)
                <option value="{{ $bm->id }}">{{ $bm->business_name }}</option>
            @endforeach
        </select>

        {{-- 邮箱 --}}
        <input type="email" name="email" required>

        {{-- 角色 --}}
        <select name="role">
            <option value="EMPLOYEE">员工</option>
            <option value="ADMIN">管理员</option>
        </select>

        <button type="submit">发送邀请</button>
    </form>
</div>

</body>
</html>
