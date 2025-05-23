const themes = ["dark", "light"];
const themeNames = {
  dark: "☀️",
  light: "🌙",
};

const link = document.getElementById("theme-style");
const toggleBtn = document.getElementById("theme-selector");

function getPageName() {
  const path = window.location.pathname;
  const page = path.split("/").pop().split(".")[0]; // Ex: index, profil, etc.
  return page;
}

function getCssFile(theme) {
  const pageName = getPageName();
  return `./style/${pageName}${theme === "dark" ? "_dark" : ""}.css`;
}

function getCookie(name) {
  const match = document.cookie.match(new RegExp("(^| )" + name + "=([^;]+)"));
  return match ? match[2] : null;
}

function setCookie(name, value, days = 365) {
  document.cookie = `${name}=${value}; path=/; max-age=${days * 86400}`;
}

function applyTheme(theme) {
  link.href = getCssFile(theme);
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
