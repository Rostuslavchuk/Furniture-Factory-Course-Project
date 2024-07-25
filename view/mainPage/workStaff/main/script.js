document.addEventListener('DOMContentLoaded', (e) => {
    e.preventDefault();

    document.getElementById('back_btn').onclick = (e) => {
        e.preventDefault();
        window.location.href = '../../menu/index.php';
    }
    document.getElementById('production_department').onclick = (e) => {
        e.preventDefault();
        window.location.href = '../production/production_department.php';
    }
    document.getElementById('design_department').onclick = (e) => {
        e.preventDefault();
        window.location.href = '../design/design_development.php';
    }
    document.getElementById('it_department').onclick = (e) => {
        e.preventDefault();
        window.location.href = '../it/it_department.php';
    }
    document.getElementById('hr_department').onclick = (e) => {
        e.preventDefault();
        window.location.href = '../hr/hr_department.php';
    }
    document.getElementById('quality_control_department').onclick = (e) => {
        e.preventDefault();
        window.location.href = '../quality_control/quality_control_department.php';
    }
});