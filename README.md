＃	Atte(アット)
	人事評価のための勤怠管理システム　日別勤怠時間がわかります
<img width="607" alt="スクリーンショット 2023-11-16 9 06 28" src="https://github.com/kimi3223/atte/assets/139026084/fd05cea7-6a4b-42cd-9881-2deeff71f7e9">

＃＃	作成した目的
	人事評価のため
	
＃＃	アプリケーションURL
	http://localhost
	使用する際には、ログインが必要になります。
	http://localhost/register
	
＃＃	他のリポジトリ
	
＃＃	機能一覧
	新規登録機能
	ログイン機能
	休憩開始と終了の時間記録機能
	勤務開始と終了の時間記録機能
	１日の合計休憩時間記録機能
	１日の実質勤務時間記録機能
	
＃＃	使用技術
	PHP
	JavaScript
	Laravel8.83.27
	
＃＃	テーブル設計
<img width="501" alt="スクリーンショット 2023-11-16 9 06 40" src="https://github.com/kimi3223/atte/assets/139026084/c5c8337c-92cf-4dab-bf7b-f97c441b7e52">

＃＃	ER図
<img width="804" alt="スクリーンショット 2023-11-16 9 06 49" src="https://github.com/kimi3223/atte/assets/139026084/77954fe9-f64b-49ef-8dd6-19c1db41541f">

＃＃	環境構築
	Laravelフレームワークを使用し、Webサーバーのローカル環境で実行しております。
	
	＃＃条件
	PHPがインストールされている
	Composerがインストールされている
	laravelの必要な依存関係が雲ストールされている
	
	＃＃手順
	①GitHubで新規リポジトリの作成
	②ローカルリポジトリの作成
	mkdir atte
	cd atte
	git init
	③GitHubのリポジトリをローカルにクローン
	git clone https://github.com/your-username/your-repository.git
	④Laravelプロジェクトを初期化し依存関係をインストール
	cd atte
	composer install
	⑤コードを書く
	cd atte
	⑥変更をコミット
	git add .
	git commit -m "変更名"
	⑦GitHubにプッシュ
	git push origin main
