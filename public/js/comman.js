function notify(message, type = "success", href = "") {
  if (href != "") {
    Swal.fire({
      title: "",
      text: message,
      icon: type,
      buttons: false,
    });
    setTimeout(function () {
      location.href = href;
    }, 2000);
  } else {
    Swal.fire({
      title: "",
      text: message,
      icon: type,
      buttons: {
        confirm: {
          text: "OK",
          value: true,
          visible: type == "error" ? true : false,
          className: "",
          closeModal: true,
        },
      },
      timer: type == "success" ? 2000 : 50000000,
    }).then(function (dismiss) {
      if (dismiss === "timer") {
      }
    });
  }
}

function toast(message, type = "error") {
  type == "1" ? "success" : type;
  let Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener("mouseenter", Swal.stopTimer);
      toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
  });

  Toast.fire({
    icon: type,
    title: message,
  });
}
var cc = {
  headers: {
    "Content-Type": "application/x-www-form-urlencoded;charset=utf-8;",
  },
};

function getdate(unix_timestamp) {
  var months_arr = [
    "01",
    "02",
    "03",
    "04",
    "05",
    "06",
    "07",
    "08",
    "09",
    "10",
    "11",
    "12",
  ];
  var d = new Date(unix_timestamp * 1000);
  var year = d.getFullYear();
  var month = months_arr[d.getMonth()];
  var day = d.getDate();
  day = day < 10 ? "0" + day : day;

  return year + "-" + month + "-" + day;
}
function chk_otp(str) {
  return /^[0-9]{6}$/.test(str);
}

function chk_float(str) {
  return /^[+-]?((\.\d+)|(\d+(\.\d+)?))$/.test(str);
}
function chk_space(str) {
  if (str.indexOf(" ") !== -1) return true;
  else return false;
}
function chk_bankaccount(str) {
  return /^[0-9]{15,25}$/.test(str);
}
function chk_number(str) {
  return /^\d+$/.test(str);
}
function chk_string(str) {
  return /^[a-zA-Z ]{2,}$/.test(str);
}

function chk_url(str) {
  return /(http(s?):\/\/)([a-z0-9\-]+\.)+[a-z]{2,4}(\.[a-z]{2,4})*(\/[^ ]+)*/i.test(
    str
  );
}
function chk_name(str) {
  return /^[a-zA-Z]{2,30}$/.test(str);
}

function chk_username(str) {
  return /^[a-zA-Z0-9_]{5,15}$/.test(str);
}
function chk_phone(str) {
  return /^[0-9]{8,15}$/.test(str);
}
function chk_recommended(str) {
  return /[A-Za-z0-9]+/.test(str);
}

function chk_email(str) {
  const re =
    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(str).toLowerCase());
}

function chk_password(str) {
  return /^\S*(?=\S{8,30})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[!\\/\\\\\"#$%&'()*+,-.\\:;<=>?@[\]^_`{|}~])\S*$/.test(
    str
  );
}

function chk_ip(str) {
  return /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/.test(str);
}

function chk_alias(str) {
  return /^[a-zA-Z0-9-_.()]+$/.test(str);
}

function ch_alphnum(str) {
  return /^[a-zA-Z0-9]*$/.test(str);
}

function convert(unix_timestamp) {
  var months_arr = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
  ];
  var date = new Date(unix_timestamp * 1000);
  var year = date.getFullYear();
  var month = months_arr[date.getMonth()];
  var day = date.getDate();
  var hours = date.getHours();
  var newformat = hours >= 12 ? "PM" : "AM";
  hours = hours % 12;
  hours = hours ? hours : 12;
  var minutes = "0" + date.getMinutes();
  var seconds = "0" + date.getSeconds();
  return (
    day +
    " " +
    month +
    " " +
    year +
    " " +
    hours +
    ":" +
    minutes.substr(-2) +
    " " +
    newformat
  );
}

function convertTotmstmp(date) {
  date = date.replace(" ", "T");
  return Date.parse(date) / 1000;
}

function chk_maximumlength(str, character) {
  var strlength = str.length;
  return strlength < character ? false : true;
}

function cons(data) {
  console.log(data);
}
