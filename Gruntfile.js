module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        cssmin: {
            css: {
                src: 'css/mapField.css',
                dest: 'css/mapField.min.css'
            }
        },
        uglify: {
            js: {
			    files : {
			        'javascript/google/mappablegoogle.min.js' : [
				        'javascript/google/FullScreenControl.js',
				        'javascript/google/markerclusterer.js',
				        'javascript/google/maputil.js'
			        ],

			        'javascript/mapField.min.js' : [
				         'javascript/mapField.js'
			        ]

			    }
			  },
        },
    });
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.registerTask('default', ['cssmin:css', 'uglify:js']);
};
