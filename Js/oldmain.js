function updateTime() {
    var currentTime = new Date();
    var hours = currentTime.getHours();
    var minutes = currentTime.getMinutes();
    var seconds = currentTime.getSeconds();
    var dayOfWeek = getDayOfWeek(currentTime.getDay());
    var day = currentTime.getDate();
    var month = getMonthName(currentTime.getMonth());
    var year = currentTime.getFullYear();

    minutes = (minutes < 10 ? "0" : "") + minutes;
    seconds = (seconds < 10 ? "0" : "") + seconds;

    var timeString = hours + ':' + minutes + ':' + seconds;
    var dateString = dayOfWeek + ' ' + day + ' ' + month + ' ' + year;

    var dateTimeString = timeString + ' ' + dateString;

    document.querySelector(".date-time").innerHTML = dateTimeString;

    setTimeout(updateTime, 1000);
}

function getDayOfWeek(dayIndex) {
    var daysOfWeek = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
    return daysOfWeek[dayIndex];
}

function getMonthName(monthIndex) {
    var monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
    return monthNames[monthIndex];
}

updateTime();

function toggleComingSoonText(item) {
    if (item.textContent === 'Financement') {
        item.textContent = 'Bientôt disponible';
    } else if (item.textContent === 'Investissement') {
        item.textContent = 'Bientôt disponible';
    }

    setTimeout(function() {
        if (item.textContent === 'Bientôt disponible') {
            item.textContent = item.getAttribute('data-original-text');
        }
    }, 2000);
}

$(document).ready(function(){
    $('.change-text').click(function(){
        event.preventDefault();
        var $element = $(this);
        var originalText = $element.text();
        $element.text('Bientôt disponible');
        
        setTimeout(function(){
            $element.text(originalText);
        }, 2000);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const dropdownContainers = document.querySelectorAll(".dropdown, .custom-dropdown");
    const settingsDropdowns = document.querySelectorAll(".dropdown-content, .custom-dropdown-content");

    function isInsideDropdown(element) {
        return Array.from(dropdownContainers).some(container => container.contains(element));
    }

    dropdownContainers.forEach(container => {
        container.addEventListener("click", function (event) {
            const isSettingsBtn = event.target.closest("#settingsBtn, .custom-dropbtn");
            const dropdownContent = this.querySelector(".dropdown-content, .custom-dropdown-content");
            
            if (isSettingsBtn) {
                event.preventDefault();
                const isVisible = window.getComputedStyle(dropdownContent).display !== "none";
                dropdownContent.style.display = isVisible ? "none" : "block";
            } else {
                dropdownContent.style.display = "none";
            }
        });
    });

    document.addEventListener("click", function (event) {
        if (!isInsideDropdown(event.target)) {
            settingsDropdowns.forEach(dropdown => {
                dropdown.style.display = "none";
            });
        }
    });
});

function toggleMenu() {
    const menu = document.querySelector('.menu');
    const authLinks = document.querySelector('.auth-links');
    menu.classList.toggle('active');
    authLinks.classList.toggle('active');
}

$(document).ready(function() {
    $('#showFilterBtn').click(function() {
        $('body').toggleClass('show-filter');

        var buttonText = $(this).text();
        
        if ($('body').hasClass('show-filter')) {
            $(this).text('Masquer les filtres');
        } else {
            $(this).text('Afficher les filtres');
        }
    });
});