// 一覧表印刷ダイアログ
function showListDialog(downloadRoute, displayRoute, title) {
    document.getElementById('listDialogOverlay').style.display = 'block';
    document.getElementById('listDialogBox').style.display = 'block';
    document.getElementById('listDialogTitle').textContent = title;

    document.getElementById('listDownloadOption').addEventListener('click', function() {
        window.location.href = downloadRoute;
        closeListDialog();
    });

    document.getElementById('listDisplayOption').addEventListener('click', function() {
        window.location.href = displayRoute;
        closeListDialog();
    });

    document.getElementById('listCloseOption').addEventListener('click', function() {
        closeListDialog();
    });

    document.getElementById('closeListDialog').addEventListener('click', function() {
        closeListDialog();
    });

    function closeListDialog() {
        document.getElementById('listDialogOverlay').style.display = 'none';
        document.getElementById('listDialogBox').style.display = 'none';
    }
}

// はがき印刷ダイアログ
function showPostcardDialog(title, routes) {
    document.getElementById('postcardDialogOverlay').style.display = 'block';
    document.getElementById('postcardDialogBox').style.display = 'block';
    document.getElementById('postcardDialogTitle').textContent = title;

    document.getElementById('postcardDownloadOption').addEventListener('click', function() {
        const selectedSize = document.querySelector('input[name="paperSize"]:checked').value;
        console.log("Selected paper size:", selectedSize);
        let downloadRoute, displayRoute;

        switch (selectedSize) {
            case 'postcard_print':
                downloadRoute = routes.postcard.download;
                displayRoute = routes.postcard.display;
                break;
            case 'envelope4_print':
                downloadRoute = routes.envelope4.download;
                displayRoute = routes.envelope4.display;
                break;
            case 'envelope3_print':
                downloadRoute = routes.envelope3.download;
                displayRoute = routes.envelope3.display;
                break;
            case 'square3_print':
                downloadRoute = routes.square3.download;
                displayRoute = routes.square3.display;
                break;
            case 'square2_print':
                downloadRoute = routes.square2.download;
                displayRoute = routes.square2.display;
                break;
            case 'label_print':
                downloadRoute = routes.label.download;
                displayRoute = routes.label.display;
                break;
            default:
                downloadRoute = routes.postcard.download;
                displayRoute = routes.postcard.display;
        }

        closePostcardDialog();
        showListDialog(downloadRoute, displayRoute, "宛名印刷");
    });
    document.getElementById('postcardDisplayOption').addEventListener('click', function() {
        closePostcardDialog();
        showListDialog(routes.postcard.download, routes.postcard.display, "裏面印刷");
    })

    document.getElementById('postcardCloseOption').addEventListener('click', function() {
        closePostcardDialog();
    });

    document.getElementById('closePostcardDialog').addEventListener('click', function() {
        closePostcardDialog();
    });

    function closePostcardDialog() {
        document.getElementById('postcardDialogOverlay').style.display = 'none';
        document.getElementById('postcardDialogBox').style.display = 'none';
    }
}

// はがき印刷ダイアログ裏面印刷なし
function showPostcardDialogNoBackPrint(title, routes) {
    document.getElementById('postcardDialogOverlayNoBackPrint').style.display = 'block';
    document.getElementById('postcardDialogBoxNoBackPrint').style.display = 'block';
    document.getElementById('postcardDialogTitleNoBackPrint').textContent = title;

    document.getElementById('postcardDownloadOptionNoBackPrint').addEventListener('click', function() {
        const selectedSize = document.querySelector('input[name="paperSizeNoBackPrint"]:checked').value;
        console.log("Selected paper size:", selectedSize);
        let downloadRoute, displayRoute;
        switch (selectedSize) {
            case 'postcard_print':
                downloadRoute = routes.postcard.download;
                displayRoute = routes.postcard.display;
                break;
            case 'envelope4_print':
                downloadRoute = routes.envelope4.download;
                displayRoute = routes.envelope4.display;
                break;
            case 'envelope3_print':
                downloadRoute = routes.envelope3.download;
                displayRoute = routes.envelope3.display;
                break;
            case 'square3_print':
                downloadRoute = routes.square3.download;
                displayRoute = routes.square3.display;
                break;
            case 'square2_print':
                downloadRoute = routes.square2.download;
                displayRoute = routes.square2.display;
                break;
            case 'label_print':
                downloadRoute = routes.label.download;
                displayRoute = routes.label.display;
                break;
            default:
                downloadRoute = routes.postcard.download;
                displayRoute = routes.postcard.display;
        }
        closePostcardDialogNoBackPrint();
        showListDialog(downloadRoute, displayRoute, "宛名印刷");
    });

    document.getElementById('postcardCloseOptionNoBackPrint').addEventListener('click', function() {
        closePostcardDialogNoBackPrint();
    });

    document.getElementById('closePostcardDialogNoBackPrint').addEventListener('click', function() {
        closePostcardDialogNoBackPrint();
    });

    function closePostcardDialogNoBackPrint() {
        document.getElementById('postcardDialogOverlayNoBackPrint').style.display = 'none';
        document.getElementById('postcardDialogBoxNoBackPrint').style.display = 'none';
    }
}