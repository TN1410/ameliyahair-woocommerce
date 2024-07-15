module.exports = function(grunt) {
  'use strict';

  var saVersion = '';
  var pkgJson = require('./package.json');

  require('matchdep').filterDev('grunt-*').forEach( grunt.loadNpmTasks );

  grunt.getPluginVersion = function() {
    var p = 'woosuite-core.php';
    if (saVersion == '' && grunt.file.exists(p)) {
        var source = grunt.file.read(p);
        var found = source.match(/Version:\s(.*)/);
        saVersion = found[1];
    }
    return saVersion;
  };

  grunt.initConfig({
    pkg: '<json:package.json>',
      compress: {
          main: {
              options: {
                archive: '../woosuite-core.v' + pkgJson.version + '.zip'
              },
              files: [
                { src: 'assets/**', dest: 'woosuite-core/' },
                { src: 'includes/**', dest: 'woosuite-core/' },
                { src: 'languages/**', dest: 'woosuite-core/' },
                { src: 'index.php', dest: 'woosuite-core/' },
                { src: 'woosuite-core.php', dest: 'woosuite-core/' },
                { src: 'phpcs.xml', dest: 'woosuite-core/' }
              ]
          }
      },
  		'string-replace': {
  			inline: {
          files: {
            './': ['woosuite-core.php']
  				},
  				options: {
  					replacements: [
              {
                pattern: 'Version: ' + grunt.getPluginVersion(),
  							replacement: 'Version: ' + pkgJson.version
  						}, {
                pattern: 'define\( \'WOOSUITE_CORE_VERSION\', \'' + grunt.getPluginVersion() + '\' );',
  							replacement: 'define\( \'WOOSUITE_CORE_VERSION\', \'' + pkgJson.version + '\' );'
  						}
  					]
  				}
  			}
  		},
      http_upload: {
        local: {
          options: {
            url: 'http://industrialmatrix.local/wp-json/woosuite-server-admin/v1/plugins/5/versions/',
            method: 'POST',
            rejectUnauthorized: false,
            headers: {
              'Content-Type': 'multipart/form-data'
            },
            data: {
              api_key : 'NUt3plqvGQXbmIllsebEAm0duRGD9De1',
              version : pkgJson.version,
              requires : '5.4.2',
              tested : '5.4.3',
              requires_php : '5.6',
              changelog: '',
              upgrade_notice: '',
              status: 'public',
            },
            onComplete: function(data) {
              console.log('Response: ' + data);
            }
          },
          src: '../woosuite-core.v' + pkgJson.version + '.zip',
          dest: 'file'
        },
        server: {
          options: {
            url: 'https://server.aovup.com/wp-json/woosuite-server-admin/v1/plugins/1/versions/',
            method: 'POST',
            rejectUnauthorized: false,
            headers: {
              'Content-Type': 'multipart/form-data'
            },
            data: {
              api_key : 'IE9oyJfmmtMZEZ1u80oe2AvazdHclhLD',
              version : pkgJson.version,
              requires : '5.4.2',
              tested : '5.4.3',
              requires_php : '5.6',
              changelog: '',
              upgrade_notice: '',
              status: 'public',
            },
            onComplete: function(data) {
              console.log('Response: ' + data);
            }
          },
          src: '../woosuite-core.v' + pkgJson.version + '.zip',
          dest: 'file'
        }
      }
  });

  //grunt.registerTask('translate', [ 'makepot' ]);
  //grunt.registerTask('version', [ 'string-replace' ]);
  grunt.registerTask('build', [ 'string-replace', 'compress' ]);
  grunt.registerTask('deploy-local', [ 'build', 'http_upload:local' ]);
  grunt.registerTask('deploy-server', [ 'build', 'http_upload:server' ]);
};
