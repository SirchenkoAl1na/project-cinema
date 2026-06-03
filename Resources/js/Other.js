
function href(url) {
    document.location.href = url;
}
// фукнція для копіювання
function Copy(value_for_copy,e) {
  navigator.clipboard.writeText(value_for_copy);
  // Показуємо повідомлення про успішне копіювання
    // e.classList.remove('button-not-visible');
    e.innerHTML = '<i class="fa-solid fa-check"></i>';
    setTimeout(() => {
    e.classList.add('button-not-visible');
        e.innerHTML = '<i class="fa-solid fa-copy"></i>';
    }, 1500);
}
// FIXME: change theme
function toggleTheme()
{
        var r = document.querySelector(':root');
    //check theme
    var rs = getComputedStyle(r);
    if(rs.getPropertyValue('--bg') === 'whitesmoke') {
        //dark theme
        r.style.setProperty('--bg', '#121212');
        r.style.setProperty('--text', '#ffffff');
        r.style.setProperty('--btn', '#1f1f1f');
    }
    else {
        //light theme
        r.style.setProperty('--bg', 'whitesmoke');
        r.style.setProperty('--text', 'rgb(95, 95, 95)');
        r.style.setProperty('--btn', 'rgb(179, 179, 179)');
    }
}