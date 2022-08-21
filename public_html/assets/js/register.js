$("#readTU").click(function(e) {
  if ((e.target).tagName == 'INPUT') return true;
  e.preventDefault();
  $("#checkTU").prop("checked", !$("#checkTU").prop("checked"));
})

$("#readPP").click(function(e) {
  if ((e.target).tagName == 'INPUT') return true;
  e.preventDefault();
  $("#checkPP").prop("checked", !$("#checkPP").prop("checked"));

  function openForm() {
    document.getElementById("registerForm").style.display = "block";
  }

  function closeForm() {
    document.getElementById('registerForm').style.display = "none";
  }
})