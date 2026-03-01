
function register() {
  const name = document.getElementById("regName").value;
  const email = document.getElementById("regEmail").value;
  const pass = document.getElementById("regPass").value;

  if (name === "" || email === "" || pass === "") {
    alert("Please fill all fields");
    return;
  }

  const user = { name, email, pass };
  localStorage.setItem("user", JSON.stringify(user));

  alert("Registration Successful!");
  window.location.href = "login.html";
}

function login() {
  const email = document.getElementById("loginEmail").value;
  const pass = document.getElementById("loginPass").value;

  const savedUser = JSON.parse(localStorage.getItem("user"));

  if (!savedUser) {
    alert("No user found. Please register first.");
    return;
  }

  if (email === savedUser.email && pass === savedUser.pass) {
    alert("Login Successful!");
    window.location.href = "index.html";
  } else {
    alert("Invalid Email or Password");
  }
}


function logout() {
  window.location.href = "login.html";
}


if (window.location.pathname.includes("index.html")) {
  const savedUser = localStorage.getItem("user");
  if (!savedUser) {
    window.location.href = "login.html";
  }
}

const doctors = [
  { name: "Dr. Rahman", available: false },
  { name: "Dr. Karim", available: true}
];

if (document.getElementById("doctorList")) {
  const list = document.getElementById("doctorList");

  doctors.forEach(doc => {
    const li = document.createElement("li");
    li.textContent = doc.name + " - " + 
      (doc.available ? "Available" : "Not Available");
    list.appendChild(li);
  });
}

function requestAppointment() {
  const name = document.getElementById("name").value.trim();

  if (name === "") {
    alert("Please enter your name");
    return;
  }

  let doctorAvailable = false;

  for (let i = 0; i < doctors.length; i++) {
    if (doctors[i].available === true) {
      doctorAvailable = true;
      break;
    }
  }

  if (doctorAvailable) {
    alert("Appointment request sent for " + name);
  } else {
    alert("Sorry, no doctor is available. Request can't be sent.");
  }
}