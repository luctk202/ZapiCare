var Axios = axios.create({
    baseURL: window.location.origin,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
});
var ProSell = {
    config: {
        ajaxProcess: false
    },
    loading: {
        show: function () {
            var _loading = $('<div  id="loading-overlay"></div>');
            $('body').append(_loading);
        },
        hide: function () {
            var _loading = $('body').find('#loading-overlay');
            _loading.remove();
        }
    },
    readURL: function (input, id) {
        if (input.files && input.files[0]) {
            let length = input.files.length
            let i = 0
            $('#preview_' + id).html('')
            while (i < length) {
                let file = input.files[i]
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#preview_' + id).append(ProSell.htmlFile(file, e))
                }
                reader.readAsDataURL(file);
                i++;
            }
        }
    },
    htmlFile: function (file, e) {
        const fileType = file['type'];
        const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
        if (!validImageTypes.includes(fileType)) {
            return (
                '<div style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">' +
                '<div style="width: 110px;height: 110px;padding:5px;text-align: center;padding-top: 30px"><i class="fa fa-file-alt fa-3x"></i></div>' +
                '<div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">' +
                '<div style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">' + file.name + '</div>' +
                '<small style="line-height: 20px">' + ProSell.formatBytes(file.size, 0) + '</small>' +
                '</div>' +
                '</div>'
            )
        }
        return (
            '<div style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">' +
            '<div style="width: 110px;height: 110px;padding:5px"><image style="object-fit: contain;max-height: 100%;max-width: 100%" alt="" src="' + e.target.result + '"/></div>' +
            '<div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">' +
            '<div style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">' + file.name + '</div>' +
            '<small style="line-height: 20px">' + ProSell.formatBytes(file.size, 0) + '</small>' +
            '</div>' +
            '</div>'
        )
    },
    formatBytes: function (bytes, decimals = 2) {
        if (!+bytes) return '0 Bytes'
        const k = 1024
        const dm = decimals < 0 ? 0 : decimals
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']

        const i = Math.floor(Math.log(bytes) / Math.log(k))

        return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
    }
};
