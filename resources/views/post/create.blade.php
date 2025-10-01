<form action="{{ route('store') }}" method="POST" class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    @csrf
    <label for="tweet" class="block text-sm font-medium text-gray-700 mb-2">Что у тебя нового?</label>
    <textarea id="tweet" name="tweet" rows="3"
        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
        placeholder="Напиши свой твит..."></textarea>

    <div class="mt-4 flex justify-between items-center">
        <span class="text-sm text-gray-500" id="charCount">0/280</span>
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md shadow-sm transition">
            Опубликовать
        </button>
    </div>
</form>

<script>
const textarea = document.getElementById('tweet');
const charCount = document.getElementById('charCount');

textarea.addEventListener('input', () => {
    charCount.textContent = `${textarea.value.length}/280`;
});
</script>