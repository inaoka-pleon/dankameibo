<?php
namespace App\Services;

use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class PostcardPrint
{
    // はがき
    public static function createPostcardInstance($orientation = 'P', $unit = 'mm', $format = array(100, 148), $unicode = true, $encoding = 'UTF-8') {
        // FPDIインスタンス生成
        $pdf = new Fpdi($orientation, $unit, $format, $unicode, $encoding);
        // ページ設定（最初に設定しないとヘッダーに罫線が入ってしまう）
        $pdf->setAutoPageBreak(false);
        $pdf->setTopMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(0);
        $pdf->setPrintFooter(false);

        return $pdf;
    }
    public static function loadFont($fontPath = './fonts/ipaexm.ttf') {
        $font = new TCPDF_FONTS();
        return $font->addTTFfont($fontPath);
    }
    public static function postcardName($pdf, $font, $text, $x, $y, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        $defaultSpacing = 11;
        $specialSpacing = 5;

        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            if ($char === ' ') {
                $y += $specialSpacing;
            } else {
                $y += $defaultSpacing;
            }
        }
    }
    public static function postcardPostcode($pdf, $font, $text, $positions, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        foreach (str_split($text) as $index => $char) {
            if (isset($positions[$index])) {
                $pdf->Text($positions[$index][0], $positions[$index][1], $char);
            }
        }
    }
    public static function postcardAddress($pdf, $font, $text, $x, $y, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        foreach (mb_str_split($text) as $char) {
            if ($char === '-') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 4, $y + 5.7);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } elseif ($char === 'ー') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 4.8, $y + 4);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } elseif ($char === '－') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 4.5, $y + 4.5);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } else {
                $pdf->Text($x, $y, $char);
            }
            $y += 7;
        }
    }
    // 長形4号
    public static function createEnvelope4Instance($orientation = 'P', $unit = 'mm', $format = array(90, 205), $unicode = true, $encoding = 'UTF-8') {
        // FPDIインスタンス生成
        $pdf = new Fpdi($orientation, $unit, $format, $unicode, $encoding);
        // ページ設定（最初に設定しないとヘッダーに罫線が入ってしまう）
        $pdf->setAutoPageBreak(false);
        $pdf->setTopMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(0);
        $pdf->setPrintFooter(false);

        return $pdf;
    }
    public static function Envelope4Name($pdf, $font, $text, $x, $y, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        $defaultSpacing = 13.5;
        $specialSpacing = 6;

        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            if ($char === ' ') {
                $y += $specialSpacing;
            } else {
                $y += $defaultSpacing;
            }
        }
    }
    public static function Envelope4Postcode($pdf, $font, $text, $positions, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        // ハイフンを除外して７桁の数字だけにする
        $text = str_replace('-', '', $text);
        foreach (str_split($text) as $index => $char) {
            if (isset($positions[$index])) {
                $pdf->Text($positions[$index][0], $positions[$index][1], $char);
            }
        }
    }
    public static function Envelope4Address($pdf, $font, $text, $x, $y, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        foreach (mb_str_split($text) as $char) {
            if ($char === '-') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 4, $y + 7.2);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } elseif ($char === 'ー') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 5.3, $y + 5.1);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } elseif ($char === '－') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 5.2, $y + 5.2);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } else {
                $pdf->Text($x, $y, $char);
            }
            $y += 8.5;
        }
    }
    // 長形3号
    public static function createEnvelope3Instance($orientation = 'P', $unit = 'mm', $format = array(120, 235), $unicode = true, $encoding = 'UTF-8') {
        // FPDIインスタンス生成
        $pdf = new Fpdi($orientation, $unit, $format, $unicode, $encoding);
        // ページ設定（最初に設定しないとヘッダーに罫線が入ってしまう）
        $pdf->setAutoPageBreak(false);
        $pdf->setTopMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(0);
        $pdf->setPrintFooter(false);

        return $pdf;
    }
    public static function Envelope3Name($pdf, $font, $text, $x, $y, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        $defaultSpacing = 18;
        $specialSpacing = 8;

        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            if ($char === ' ') {
                $y += $specialSpacing;
            } else {
                $y += $defaultSpacing;
            }
        }
    }
    public static function Envelope3Postcode($pdf, $font, $text, $positions, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        // ハイフンを除外して７桁の数字だけにする
        $text = str_replace('-', '', $text);
        foreach (str_split($text) as $index => $char) {
            if (isset($positions[$index])) {
                $pdf->Text($positions[$index][0], $positions[$index][1], $char);
            }
        }
    }
    public static function Envelope3Address($pdf, $font, $text, $x, $y, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        foreach (mb_str_split($text) as $char) {
            if ($char === '-') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 4.05, $y + 8.05);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } elseif ($char === 'ー') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 5.5, $y + 5.5);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } elseif ($char === '－') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 5.6, $y + 5.6);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } else {
                $pdf->Text($x, $y, $char);
            }
            $y += 9.5;
        }
    }
    // 角形3号
    public static function createSquare3Instance($orientation = 'P', $unit = 'mm', $format = array(216, 277), $unicode = true, $encoding = 'UTF-8') {
        // FPDIインスタンス生成
        $pdf = new Fpdi($orientation, $unit, $format, $unicode, $encoding);
        // ページ設定（最初に設定しないとヘッダーに罫線が入ってしまう）
        $pdf->setAutoPageBreak(false);
        $pdf->setTopMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(0);
        $pdf->setPrintFooter(false);

        return $pdf;
    }
    public static function Square3Name($pdf, $font, $text, $x, $y, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        $defaultSpacing = 20;
        $specialSpacing = 8;

        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            if ($char === ' ') {
                $y += $specialSpacing;
            } else {
                $y += $defaultSpacing;
            }
        }
    }
    public static function Square3Postcode($pdf, $font, $text, $positions, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        // ハイフンを除外して７桁の数字だけにする
        $text = str_replace('-', '', $text);
        foreach (str_split($text) as $index => $char) {
            if (isset($positions[$index])) {
                $pdf->Text($positions[$index][0], $positions[$index][1], $char);
            }
        }
    }
    public static function Square3Address($pdf, $font, $text, $x, $y, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        foreach (mb_str_split($text) as $char) {
            if ($char === '-') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 5, $y + 11.3);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } elseif ($char === 'ー') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 7.5, $y + 7.5);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } elseif ($char === '－') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 7.5, $y + 7.5);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } else {
                $pdf->Text($x, $y, $char);
            }
            $y += 12.5;
        }
    }
    // 角形2号
    public static function createSquare2Instance($orientation = 'P', $unit = 'mm', $format = array(240, 332), $unicode = true, $encoding = 'UTF-8') {
        // FPDIインスタンス生成
        $pdf = new Fpdi($orientation, $unit, $format, $unicode, $encoding);
        // ページ設定（最初に設定しないとヘッダーに罫線が入ってしまう）
        $pdf->setAutoPageBreak(false);
        $pdf->setTopMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(0);
        $pdf->setPrintFooter(false);

        return $pdf;
    }
    public static function Square2Name($pdf, $font, $text, $x, $y, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        $defaultSpacing = 24;
        $specialSpacing = 10;

        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            if ($char === ' ') {
                $y += $specialSpacing;
            } else {
                $y += $defaultSpacing;
            }
        }
    }
    public static function Square2Postcode($pdf, $font, $text, $positions, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        // ハイフンを除外して７桁の数字だけにする
        $text = str_replace('-', '', $text);
        foreach (str_split($text) as $index => $char) {
            if (isset($positions[$index])) {
                $pdf->Text($positions[$index][0], $positions[$index][1], $char);
            }
        }
    }
    public static function Square2Address($pdf, $font, $text, $x, $y, $fontSize) {
        $pdf->setFont($font, '', $fontSize);
        foreach (mb_str_split($text) as $char) {
            if ($char === '-') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 6, $y + 13.6);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } elseif ($char === 'ー') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 9.2, $y + 9.2);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } elseif ($char === '－') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 9.2, $y + 9.2);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } else {
                $pdf->Text($x, $y, $char);
            }
            $y += 15.5;
        }
    }
    // ラベル
    public static function createLabelInstance($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8') {
        // FPDIインスタンス生成
        $pdf = new Fpdi($orientation, $unit, $format, $unicode, $encoding);
        // ページ設定（最初に設定しないとヘッダーに罫線が入ってしまう）
        $pdf->setAutoPageBreak(false);
        $pdf->setTopMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(0);
        $pdf->setPrintFooter(false);

        return $pdf;
    }
    public static function printLabel($pdf, $f, $name, $keishou, $postcode, $address, $xOffset, $yOffset) {
        // 敬称がない場合は「様」を追加
        if (is_null($keishou) || $keishou === '') {
            $keishou = '様';
        }

        // 文字を出力、位置指定(氏名)
        $pdf->setFont($f, '', 12);
        $pdf->Text(45 + $xOffset, 48 + $yOffset, $name . '  ' . $keishou);

        // 郵便番号がある場合のみ「〒」を表示
        if (!is_null($postcode) && $postcode !== '') {
            // 文字を出力、位置指定(郵便番号)
            $pdf->setFont($f, '', 12);
            $pdf->Text(30 + $xOffset, 26 + $yOffset, '〒' . $postcode);
        }

        // 文字を出力、位置指定(住所)
        $pdf->setFont($f, '', 12);
        $pdf->MultiCell(67, 26, $address, 0, 'L', 0, 0, 30 + $xOffset, 32 + $yOffset);
    }
    // はがき裏面
    public static function createDocumentInstance($orientation = 'P', $unit = 'mm', $format = array(100, 148), $unicode = true, $encoding = 'UTF-8') {
        // FPDIインスタンス生成
        $pdf = new Fpdi($orientation, $unit, $format, $unicode, $encoding);
        // ページ設定（最初に設定しないとヘッダーに罫線が入ってしまう）
        $pdf->setAutoPageBreak(false);
        $pdf->setTopMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(0);
        $pdf->setPrintFooter(false);

        // フォントの読み込み
        $font = new TCPDF_FONTS();
        $fontPath = './fonts/ipaexm.ttf';
        $f = $font->addTTFfont($fontPath);

        // テンプレートの読み込み
        $templatePath = resource_path('template/GeneralPostcard.pdf');
        $pdf->setSourceFile($templatePath);
        $templateId = $pdf->importPage(1);

        return [$pdf, $f, $templateId];
    }
    public static function DocumentTitle($pdf, $font, $text, $x, $y, $fontSize, $lineHeight) {
        $pdf->setFont($font, '', $fontSize);
        foreach (mb_str_split($text) as $char) {
            if ($char === '（' || $char === '）' || $char === '「' || $char === '」') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 4.8, $y + 4.8);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } elseif ($char === '(' || $char === ')') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 3.6, $y + 6.8);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } elseif ($char === '、' || $char === '。') {
                $pdf->StartTransform();
                $pdf->Text($x + 5, $y - 5, $char);
                $pdf->StopTransform();
            } else {
                $pdf->Text($x, $y, $char);
            }
            $y += $lineHeight;
        } 
    }
    public static function DocumentKakui($pdf, $font, $text, $x, $y, $fontSize, $lineHeight) {
        $pdf->setFont($font, '', $fontSize);
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += $lineHeight;
        }
    }
    public static function DocumentAddress($pdf, $font, $text, $x, $y, $fontSize, $lineHeight) {
        $pdf->setFont($font, '', $fontSize);
        foreach (mb_str_split($text) as $char) {
            if ($char === '-') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 2.6, $y + 3.5);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } else {
                $pdf->Text($x, $y, $char);
            }
            $y += $lineHeight;
        }
    }
    public static function DocumentTempleName($pdf, $font, $text, $x, $y, $fontSize, $lineHeight) {
        $pdf->setFont($font, '', $fontSize);
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += $lineHeight;
        }
    }
    public static function DocumentTel($pdf, $font, $text, $x, $y, $fontSize, $lineHeight) {
        $pdf->setFont($font, '', $fontSize);
        foreach (mb_str_split($text) as $char) {
            if ($char === '-') {
                $pdf->StartTransform();
                $pdf->Rotate(270, $x + 2.5, $y + 3.2);
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
            } else {
                $pdf->Text($x, $y, $char);
            }
            $y += $lineHeight;
        }
    }
    public static function DocumentDocument($pdf, $font, $documents, $xStart, $yStart, $fontSize, $lineHeight)
    {
        $x = $xStart;
        foreach ($documents as $document) {
            if ($document) {
                $pdf->setFont($font, '', $fontSize);
                $text = $document;
                $y = $yStart;
                foreach (mb_str_split($text) as $char) { 
                    if ($char === '（' || $char === '）' || $char === '「' || $char === '」') {
                        $pdf->StartTransform();
                        $pdf->Rotate(270, $x + 2.9, $y + 2.9);
                        $pdf->Text($x, $y, $char);
                        $pdf->StopTransform();
                    } elseif ($char === '-') {
                        $pdf->StartTransform();
                        $pdf->Rotate(270, $x + 2.5, $y + 3.6);
                        $pdf->text($x, $y, $char);
                        $pdf->StopTransform();
                    } elseif ($char === 'ー' || $char === '―') {
                        $pdf->StartTransform();
                        $pdf->Rotate(270, $x + 3.1, $y + 2.6);
                        $pdf->text($x, $y, $char);
                        $pdf->StopTransform();
                    } elseif ($char === '、' || $char === '。') {
                        $pdf->StartTransform();;
                        $pdf->Text($x + 3.1, $y - 3.1, $char);
                        $pdf->StopTransform();
                    } else {
                        $pdf->Text($x, $y, $char);
                    }
                    $y += $lineHeight;
                }
            }
            $x -= 6;
        }
    }
    public static function DocumentDate($pdf, $font, $text, $x, $y, $fontSize, $lineHeight)
    {
        $pdf->setFont($font, '', $fontSize);
        foreach (mb_str_split(CommonUtility::parseNumber($text)) as $char) {
            $pdf->Text($x, $y, $char);
            $y += $lineHeight;
        }
    }
}