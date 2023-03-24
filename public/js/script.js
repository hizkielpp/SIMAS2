const hamburger = document.getElementById("hamburger");
const navbar = document.getElementById("navbar");
const sidebar = document.getElementById("sidebar");
const main = document.getElementById("main");
const iconText = document.getElementsByClassName("text");
console.log(iconText);
const brandText = document.getElementById("brandText");

console.log(iconText);

hamburger.addEventListener("click", function () {
  //   navbar.classList.toggle("slide");
  sidebar.classList.toggle("close");
  navbar.classList.toggle("close");
  main.classList.toggle("close");
  brandText.classList.toggle("close");
  iconText.classList.toggle("close");
  console.log("tes");
});
