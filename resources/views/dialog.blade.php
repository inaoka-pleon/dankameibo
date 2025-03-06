<!-- 一覧表印刷ダイアログ -->
<div id="listDialogOverlay" class="dialog-overlay"></div>
<div id="listDialogBox" class="dialog-box">
    <button id="closeListDialog" class="close-dialog">&times;</button>
    <h3 id="listDialogTitle" class="dialog-title"></h3>
    <div class="dialog-buttons">
        <button id="listDownloadOption" class="buttons print-button">ダウンロード</button>
        <button id="listDisplayOption" class="buttons display-button">画面で見る</button>
        <button id="listCloseOption" class="buttons close-button">終了</button>
    </div>
</div>

<!-- 用紙サイズ選択ダイアログ -->
<div id="postcardDialogOverlay" class="dialog-overlay"></div>
<div id="postcardDialogBox" class="dialog-box">
    <button id="closePostcardDialog" class="close-dialog">&times;</button>
    <h3 id="postcardDialogTitle" class="dialog-title"></h3>
    <div class="dialog-content">
        <form id="postcardForm">
            <div>
                <label>
                    <input type="radio" name="paperSize" value="postcard_print" checked> はがき
                </label>
            </div>
            <div>
                <label>
                    <input type="radio" name="paperSize" value="envelope4_print"> A封筒(長形4号)
                </label>
            </div>
            <div>
                <label>
                    <input type="radio" name="paperSize" value="envelope3_print"> B封筒(長形3号)
                </label>
            </div>
            <div>
                <label>
                    <input type="radio" name="paperSize" value="square3_print"> C封筒(角形3号)
                </label>
            </div>
            <div>
                <label>
                    <input type="radio" name="paperSize" value="square2_print"> D封筒(角形2号)
                </label>
            </div>
            <div>
                <label>
                    <input type="radio" name="paperSize" value="label_print"> ラベル用紙
                </label>
            </div>
        </form>
    </div>
    <div class="dialog-buttons">
        <button id="postcardDownloadOption" class="buttons print-button">宛名印刷</button>
        <button id="postcardDisplayOption" class="buttons display-button">裏面印刷</button>
        <button id="postcardCloseOption" class="buttons close-button">キャンセル</button>
    </div>
</div>

<!-- ダウンロード/表示選択ダイアログ -->
<div id="downloadDialogOverlay" class="dialog-overlay"></div>
<div id="downloadDialogBox" class="dialog-box">
    <button id="closeDownloadDialog" class="close-dialog">&times;</button>
    <h3 id="downloadDialogTitle" class="dialog-title"></h3>
    <div class="dialog-buttons">
        <button id="downloadOption" class="buttons print-button">ダウンロード</button>
        <button id="displayOption" class="buttons display-button">画面で見る</button>
        <button id="closeOption" class="buttons close-button">終了</button>
    </div>
</div>

<!-- 用紙サイズ選択ダイアログ裏面印刷なし -->
<div id="postcardDialogOverlayNoBackPrint" class="dialog-overlay"></div>
<div id="postcardDialogBoxNoBackPrint" class="dialog-box">
    <button id="closePostcardDialogNoBackPrint" class="close-dialog">&times;</button>
    <h3 id="postcardDialogTitleNoBackPrint" class="dialog-title"></h3>
    <div class="dialog-content">
        <form id="postcardFormNoBackPrint">
            <div>
                <label>
                    <input type="radio" name="paperSizeNoBackPrint" value="postcard_print" checked> はがき
                </label>
            </div>
            <div>
                <label>
                    <input type="radio" name="paperSizeNoBackPrint" value="envelope4_print"> A封筒(長形4号)
                </label>
            </div>
            <div>
                <label>
                    <input type="radio" name="paperSizeNoBackPrint" value="envelope3_print"> B封筒(長形3号)
                </label>
            </div>
            <div>
                <label>
                    <input type="radio" name="paperSizeNoBackPrint" value="square3_print"> C封筒(角形3号)
                </label>
            </div>
            <div>
                <label>
                    <input type="radio" name="paperSizeNoBackPrint" value="square2_print"> D封筒(角形2号)
                </label>
            </div>
            <div>
                <label>
                    <input type="radio" name="paperSizeNoBackPrint" value="label_print"> ラベル用紙
                </label>
            </div>
        </form>
    </div>
    <div class="dialog-buttons">
        <button id="postcardDownloadOptionNoBackPrint" class="buttons print-button">宛名印刷</button>
        <button id="postcardCloseOptionNoBackPrint" class="buttons close-button">キャンセル</button>
    </div>
</div>

<!-- ダウンロード/表示選択ダイアログ -->
<div id="downloadDialogOverlayNoBackPrint" class="dialog-overlay"></div>
<div id="downloadDialogBoxNoBackPrint" class="dialog-box">
    <button id="closeDownloadDialogNoBackPrint" class="close-dialog">&times;</button>
    <h3 id="downloadDialogTitleNoBackPrint" class="dialog-title"></h3>
    <div class="dialog-buttons">
        <button id="downloadOptionNoBackPrint" class="buttons print-button">ダウンロード</button>
        <button id="displayOptionNoBackPrint" class="buttons display-button">画面で見る</button>
        <button id="closeOptionNoBackPrint" class="buttons close-button">終了</button>
    </div>
</div>