$(document).ready(function () {
    $('#class-code').change(function () {
        teacherId = $('#class-code').val();
        if (teacherId == null) {
            route = '/load_class_subjects/' + $(this).val() + '/' + '0';
        } else {
            route = '/load_class_subjects/' + $(this).val() + '/' + $('#teacher-code').val();
        }
        $.ajax({
            type: 'get',
            url: route,
            success: function (data, txt, xhr) {
                if (xhr.status === 200) {
                    $('#sm-func').html(data.class_subject_list)
                    $('#class-name').html(data.class_name)
                    $('#us-func').html(data.teacher_subject_list)
                }
            }
        });
    })

    $('#teacher-code').change(function () {
        classCode = $('#class-code').val();
        if (classCode == null) {
            route = '/load_teacher_subjects/' + $(this).val() + '/' + '0';
        } else {
            route = '/load_teacher_subjects/' + $(this).val() + '/' + $('#class-code').val();

        }
        $.ajax({
            type: 'get',
            url: route,
            success: function (data, txt, xhr) {
                if (xhr.status === 200) {
                    $('#us-func').html(data.teacher_subject_list)
                    $('#teacher-name').html(data.teacher_name)
                    $('#sm-func').html(data.class_subject_list)
                }
            }
        });
    })
})
