const themes = ["dark", "light"];
const themeFiles = {
  dark: "./style/index_dark.css",
  light: "./style/index.css"
};

const themeNames = {
  dark: "☀️",
  light: "🌙",
};

const link = document.getElementById("theme-style");
const toggleBtn = document.getElementById("theme-selector");


function getCookie(name) {
  const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  return match ? match[2] : null;
}


function setCookie(name, value, days = 365) {
  document.cookie = `${name}=${value}; path=/; max-age=${days * 86400}`;
}


function applyTheme(theme) {
  link.href = themeFiles[theme] || themeFiles.dark;
  toggleBtn.textContent = themeNames[theme] || themeNames.dark;
  setCookie("theme", theme);
}


function cycleTheme(current) {
  const index = themes.indexOf(current);
  const nextIndex = (index + 1) % themes.length;
  return themes[nextIndex];
}

window.addEventListener("DOMContentLoaded", () => {
  let currentTheme = getCookie("theme");
  if (!themes.includes(currentTheme)) currentTheme = "dark";
  applyTheme(currentTheme);

  toggleBtn.addEventListener("click", (e) => {
    e.preventDefault();
    currentTheme = cycleTheme(currentTheme);
    applyTheme(currentTheme);
  });
});