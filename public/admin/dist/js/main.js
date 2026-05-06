var config = {};

var init_function = {
    init: function () {
        let _this = this;
        _this.bs_input_file();
        _this.showImage();
        _this.preview();
    },
    bs_input_file: function () {
        $(".input-file").before(
            function() {
                if ( ! $(this).prev().hasClass('input-ghost') ) {
                    var element = $("<input type='file' class='input-ghost' id='input_img' style='visibility:hidden; height:0'>");
                    element.attr("name",$(this).attr("name"));
                    element.change(function(){
                        element.next(element).find('input').val((element.val()).split('\\').pop());
                    });
                    $(this).find("button.btn-choose").click(function(){
                        element.click();
                    });
                    $(this).find("button.btn-reset").click(function(){
                        element.val(null);
                        $(this).parents(".input-file").find('input').val('');
                    });
                    $(this).find('input').css("cursor","pointer");
                    $(this).find('input').mousedown(function() {
                        $(this).parents('.input-file').prev().click();
                        return false;
                    });
                    return element;
                }
            }
        );
    },
    showImage: function() {
        $("#input_img").change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#image_render').attr('src', e.target.result);
                    $('#image_render').css('height', '150px');
                    $('#image_render').css('display', 'block');
                }

                reader.readAsDataURL(this.files[0]);
            }
        });
    },
    preview : function () {
        $(".btn-preview").click(function (event) {
            event.preventDefault();
            let url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
            }).done(function (result) {
                if (result.html)
                {
                    $("#preview").html('').append(result.html);
                    $(".preview").modal('show');
                }
            })
        })
    }
}

$(function () {
    init_function.init();
    $('.btn-confirm-delete').confirm({
        title: 'Xóa dữ liệu',
        content: "Bạn có chăc chắn muốn xóa dữ liệu ?",
        icon: 'fa fa-warning',
        type: 'red',
        buttons: {
            confirm: {
                text: 'Xác nhận',
                btnClass: 'btn-blue',
                action: function () {
                    location.href = this.$target.attr('href');
                }
            },
            cancel: {
                text: 'Hủy',
                btnClass: 'btn-danger',
                action: function () {
                }
            }
        }
    });

    $("#check-all").click(function () {
        $('input.check_auto_clearing:checkbox').prop('checked', $(this).is(':checked'));
    });
    $('.update-user-register').click(function () {

        var url = $(this).attr('url')
        var event_id = $(this).attr('event_id')
        var status = $('.status').val();
        var note  = $('.note').val();
        var ids = new Array();
        $('[name="id[]"]:checked').each(function()
        {
            ids.push($(this).val());
        });
        if (ids.length == 0) {
            $.confirm({
                title: 'Thông báo',
                content: 'Bạn cần chọn thành viên muốn cập nhật',
                buttons: {
                    ok: {
                        text: "OK",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(){
                        }
                    }
                }
            });
            return false;
        }

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {
                status: status,
                note: note,
                ids: ids,
                event_id: event_id,
            }
        }).done(function (result) {
            if (result.status_code == 200) {
                toastr.success('Cập nhật thành công', {timeOut: 3000});
                setTimeout(function () {
                    $url = window.location.href;
                    window.location.href = $url;
                }, 1000)
            } else {
                toastr.error('Cập nhật thất bại', {timeOut: 3000});
                setTimeout(function () {
                    $url = window.location.href;
                    window.location.href = $url;
                }, 1000)
            }
        }).fail(function (XMLHttpRequest, textStatus, thrownError) {
            console.log(thrownError)
        });
    })

    $('.address').on('change', function() {
        let $this = $(this);
        let $type = $this.attr('data-type');
        let $id   = $this.val();
        let $url = loadLocation;
        if ($type && $id)
        {
            $.ajax({
                url : $url,
                type : 'post',
                dataType: 'json',
                async: true,
                data: { id : $id,type : $type}
            }).done(function (responsive) {

                if (responsive.locations)
                {
                    // if($name_form === 'update') {
                    //
                    // }

                    let html = '';
                    if($type === 'district') {
                        html = "<option value=''> Tỉnh / Quận huyện </option>";
                    } else if($type === 'street') {
                        html = "<option value=''> Phường / Xã </option>";
                    }

                    $.each(responsive.locations, function(index,value){
                        html += "<option value='"+value.id+"'>"+value.loc_name+"</option>"
                    });

                    $('.'+$type).html(html);
                }
            });
        };
    });

    $('.jump_time').change(function () {
        var jump = $(this).val();
        $.ajax({
            url: loadListTimes,
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {
                jump: jump
            }
        }).done(function (result) {
            if (result.status_code == 200) {
                $('.schedule_time').html(result.html);
            }
        }).fail(function (XMLHttpRequest, textStatus, thrownError) {
            console.log(thrownError)
        });
    })

    $('.update_booking').click(function () {
        var url = $(this).attr('url');
        location.href = url;
    })

    $('#change-clinic').change(function () {

        var clinic_id = $(this).val();

        $.ajax({
            url: loadSpecialty,
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {
                clinic_id: clinic_id
            }
        }).done(function (result) {
            if (result.code == 200) {
                $('#specialty_id').html(result.html);
            }
        }).fail(function (XMLHttpRequest, textStatus, thrownError) {
            console.log(thrownError)
        });
    })
})
