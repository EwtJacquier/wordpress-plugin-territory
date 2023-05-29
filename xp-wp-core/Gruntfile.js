module.exports = function( grunt ) {

	'use strict';

	const config = grunt.file.readJSON( 'package.json' );
	const args = process.argv.slice(2);

	let name = config.name;
	let path = './';

	if (typeof(args[1]) !== 'undefined'){
		if (args[1].indexOf('--folder=') > -1){
			name = args[1].replace('--folder=','');
			path = '../'+name+'/';
		}
	}

	const readme_path = path + 'README.md';

	// Project configuration
	const initConfigJSON = {

		pkg: config,

		addtextdomain: {
			options: {
				textdomain: name,
			},
			update_all_domains: {
				options: {
					updateDomains: true
				},
				src: [ '*.php', '**/*.php', '!\.git/**/*', '!bin/**/*', '!node_modules/**/*', '!tests/**/*' ]
			}
		},

		wp_readme_to_markdown: {
			your_target: {
				files: {
				}
			},
		},

		makepot: {
			target: {
				options: {
					domainPath: path + 'languages',
					exclude: [ '\.git/*', 'bin/*', 'node_modules/*', 'tests/*' ],
					mainFile: path + name + '.php',
					potFilename: name + '.pot',
					potHeaders: {
						poedit: true,
						'x-poedit-keywordslist': true
					},
					type: 'wp-plugin',
					updateTimestamp: true
				}
			}
		},
	};

	initConfigJSON.wp_readme_to_markdown.your_target.files[readme_path] = path+'readme.txt';

	grunt.initConfig( initConfigJSON );

	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
	grunt.registerTask( 'default', [ 'i18n','readme' ] );
	grunt.registerTask( 'i18n', ['addtextdomain', 'makepot'] );
	grunt.registerTask( 'readme', ['wp_readme_to_markdown'] );

	grunt.util.linefeed = '\n';
};
