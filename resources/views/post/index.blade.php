<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-3E2fZtJ5CxqkUqLoUoT9Cw2P+b3vI9N1zN+6bBl3e9H2UqG8TkXzg5PvD9GhOQ5F" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-g5OQwKtbw6FgFIkK5yL7VL6R4JmL6n1Z1Kr1ZdXxkO8wJfTVYoF9pT+HoC4Qz/4t5q+6b/vODV8KHN1w5Uhwg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    @if (session('status'))
        <div class="flash-message success">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="flash-message error">
            {{ session('error') }}
        </div>
    @endif

    <h1><a href="{{ route('create') }}">Create your weet</a></h1>

    <div>
        <table class="table table-bordered border-primary">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Tweet</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tweets as $tweet)
                    <tr>
                        <th scope="row">{{ $tweet['id'] }}</th>
                        <td><td>{{ \Carbon\Carbon::parse($tweet['created_at'])->format('d.m.Y H:i') }}</td>
                        <td>{{ $tweet['text'] }}</td>
                        <td>
                            <form action="{{ route('delete', $tweet['id']) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>



    <style>
        .flash-message {
            max-width: 500px;
            margin: 10px auto;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }

        .flash-message.success {
            background-color: #e6ffed;
            border: 1px solid #28a745;
            color: #155724;
        }

        .flash-message.error {
            background-color: #ffe6e6;
            border: 1px solid #dc3545;
            color: #721c24;
        }
    </style>
</body>

</html>
