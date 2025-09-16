=== Background Video Controller ===
Contributors:
Tags: video, background, mobile, fullscreen
Requires at least: 5.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 0.01
License: GPLv2 or later

縦動画の背景自動再生プラグイン。モバイル全画面、PC中央表示＋背景ぼかし/拡大/黒対応

== Description ==

縦動画を常時背景で再生するプラグインです。

* モバイル：全画面表示
* PC：中央に縦動画を幅固定で表示、外側を「ぼかし／拡大／黒」で埋める
* jQuery不要、外部依存なし
* 自動再生不可時はポスター画像をフォールバック表示

== Installation ==

1. プラグインをアップロードし有効化
2. 投稿・固定ページでショートコードを使用

== Usage ==

ショートコード：
`[bvc id="123" poster="456" pc_width="480" bg_mode="blur" class=""]`

== Attributes ==

* **id** (必須): 動画添付ID
* **poster** (任意): ポスター画像添付ID
* **pc_width** (任意): PC表示時の幅（既定480px）
* **bg_mode** (任意): 背景モード blur|zoom|black（既定blur）
* **class** (任意): 追加CSSクラス

== Examples ==

基本使用：
`[bvc id="123"]`

ポスター付き：
`[bvc id="123" poster="456"]`

背景黒モード：
`[bvc id="123" bg_mode="black"]`

PC幅カスタマイズ：
`[bvc id="123" pc_width="600"]`

== Notes ==

* モバイル自動再生には無音動画が必要
* 短尺動画を推奨（ユーザビリティ・SEO配慮）
* mp4/webm両形式自動検出対応

== Changelog ==

= 0.01 =
* 初回リリース