const dt = new DataTransfer(); // Permet de manipuler les fichiers de l'input file

$("#attachment").on("change", function (e) {
    let file = this.files[0];
    let fileType = file["type"];
    let validImageTypes = [
        "image/gif",
        "image/jpeg",
        "image/png",
        "image/jpg",
        "image/svg+xml",
    ];
    if ($.inArray(fileType, validImageTypes) < 0) {
        $("#uploadImage").val("");
        $("#uploadPreview").attr("src", "");
        Swal.fire({
            icon: `warning`,
            title: `ตรวจสอบ`,
            text: `รองรับไฟล์ .jpg, .jpg, png เท่านั้น`,
            showCancelButton: false,
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#3085d6",
        });
    } else {
        // $("#files-names").empty();
        for (var i = 0; i < this.files.length; i++) {
            let fileBloc = $(`<div class="file-block position-relative col-md-4"></div>`);
            let fileName = `
        <span class="name d-none">${this.files.item(i).name}</span>
        `;
            let src = URL.createObjectURL(this.files.item(i));
            fileBloc
                .append(
                    `<div class="position-absolute top-0 end-0"><span class="file-delete"><span>+</span></span> ${fileName}</div>`
                )
                .append(
                    `<div><img src="${src}" alt="preview" class="object-cover mx-auto " /></div>`
                );
            $("#filesList > #files-names").append(fileBloc);
        }

        for (let file of this.files) {
            dt.items.add(file);
        }
        this.files = dt.files;

        $("span.file-delete").click(function () {
            let name = $(this).next("span.name").text();

            $(this).parent().parent().remove();

            for (let i = 0; i < dt.items.length; i++) {
                if (name === dt.items[i].getAsFile().name) {
                    dt.items.remove(i);
                    continue;
                }
            }
            document.getElementById("attachment").files = dt.files;
            $('#add_image_cover').show();
        });
    }
});
