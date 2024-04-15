function showToast(message, success) {
    const toastContainer = document.getElementById('toastContainer');
    toastContainer.style.display = "block"; // Показываем контейнер тостов

    const toast = document.createElement('div');
    toast.className = success ? 'toast success' : 'toast error';
    toast.textContent = message;
    toastContainer.appendChild(toast);

    // Включаем видимость тоста
    setTimeout(() => {
        toast.classList.add('show');
    }, 100); // небольшая задержка для плавного появления

    setTimeout(() => {
        toast.classList.remove('show'); // начинаем скрывать
        setTimeout(() => {
            toastContainer.removeChild(toast); // полностью удаляем элемент после исчезновения
            if (toastContainer.childElementCount === 0) {
                toastContainer.style.display = 'none'; // Скрываем контейнер тостов
            }
        }, 500); // время для завершения анимации исчезновения
    }, 3000);
}
