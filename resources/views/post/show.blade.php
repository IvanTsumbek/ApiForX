<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Tweet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-3E2fZtJ5CxqkUqLoUoT9Cw2P+b3vI9N1zN+6bBl3e9H2UqG8TkXzg5PvD9GhOQ5F" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-g5OQwKtbw6FgFIkK5yL7VL6R4JmL6n1Z1Kr1ZdXxkO8wJfTVYoF9pT+HoC4Qz/4t5q+6b/vODV8KHN1w5Uhwg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container mt-5">
        <a href="{{ route('index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back to all tweets
        </a>

        <div class="card">
            <div class="card-header">
                Tweet ID: {{ $tweet['id'] }}
            </div>
            <div class="card-body">
                <p class="card-text" style="white-space: pre-line;">
                    {{ $tweet['text'] }}
                </p>
            </div>
            <div class="card-footer d-flex gap-2">
                <!-- Корзина для удаления -->
                <form action="{{ route('delete', $tweet['id']) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
