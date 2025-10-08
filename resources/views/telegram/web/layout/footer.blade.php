<script>
    // Инициализация
    const tg = window.Telegram.WebApp;
    tg.expand(); // Развернуть Web App на весь экран

    document.getElementById('sendData').addEventListener('click', () => {
        // Сформировать полезную нагрузку
        const payload = { answer: 'Привет от Mini App!' };
        // Отправить данные на сервер через метод back button
        tg.sendData(JSON.stringify(payload));
    });
</script>
</body>
</html>
