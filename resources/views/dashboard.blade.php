<div style="padding: 20px;">
    <h1>Dashboard</h1>
    
    <div style="margin: 20px 0; padding: 15px; background-color: #f0f0f0; border-radius: 5px;">
        <p><strong>Nama:</strong> {{ Auth::user()->name }}</p>
        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
    </div>
    <form method="POST" action="/logout">
        @csrf
        <button type="submit" style="padding: 10px 20px; background-color: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Logout
        </button>
    </form>
</div>