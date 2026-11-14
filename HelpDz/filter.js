function buyCourse(courseId) {
    window.location.href = 'course_info.php?course_id=' + courseId;
}

document.addEventListener("DOMContentLoaded", function () {
    const categoryCheckboxes = document.querySelectorAll('.filter1 input[name="category[]"]');
    const languageCheckboxes = document.querySelectorAll('.filter1 input[name="language[]"]');

    categoryCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            handleSingleSelection(categoryCheckboxes, checkbox);
            filterCourses();
        });
    });

    languageCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            handleSingleSelection(languageCheckboxes, checkbox);
            filterCourses();
        });
    });

    function handleSingleSelection(checkboxes, clickedCheckbox) {
        checkboxes.forEach(function (checkbox) {
            if (checkbox !== clickedCheckbox && checkbox.checked) {
                checkbox.checked = false;
            }
        });
    }

    function filterCourses() {
        const checkedCategories = getCheckedValues('.filter1 input[name="category[]"]:checked');
        const checkedLanguages = getCheckedValues('.filter1 input[name="language[]"]:checked');

        fetch('filter_courses.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                category: checkedCategories,
                language: checkedLanguages
            })
        })
        .then(response => response.text())
        .then(data => {
            document.querySelector('.courses').innerHTML = data;
            updateResultCount();
            window.translateDynamicContent();
        })
        .catch(error => console.error('Error:', error));
    }

    function updateResultCount() {
        const courseCount = document.querySelectorAll('.courses .course').length;
        document.getElementById('resultCount').textContent = courseCount;
    }

    function getCheckedValues(selector) {
        const checkedCheckboxes = document.querySelectorAll(selector);
        const checkedValues = Array.from(checkedCheckboxes).map(function (checkbox) {
            return checkbox.value;
        });
        return checkedValues;
    }

    filterCourses();
});