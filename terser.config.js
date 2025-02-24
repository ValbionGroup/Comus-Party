const path = require('path');
const fs = require('fs');
const {minify} = require("terser");

const listOfJsFiles = fs.readdirSync(path.resolve('./src/assets/js')).reduce((acc, file) => {
    acc[file.replace('.js', '')] = path.resolve(`./src/assets/js/${file}`);
    return acc;
}, {});
const currentYear = new Date().getFullYear();
const mode = process.argv[2].split('=')[1] || 'development';

const options = {
    compress: {
        drop_console: true,
        keep_fnames: true,
        keep_classnames: true,
        keep_fargs: true,
        unused: false,
    },
    mangle: {
        toplevel: false,
        keep_fnames: true,
        keep_classnames: true,
    },
    format: {
        comments: false,
        preamble: `/**\n* JavaScript minified file\n* Comus Party - Application web de mini-jeux multijoueurs en ligne\n* Copyright (c) ${currentYear} Groupe Valbion\n* Github : https://github.com/ValbionGroup/Comus-Party\n*/\n\n`,
    },
};


if (!fs.existsSync(path.resolve('./public/assets/js'))) {
    fs.mkdirSync(path.resolve('./public/assets/js'), {recursive: true});
}


if (mode === 'development') {
    for (const [key, value] of Object.entries(listOfJsFiles)) {
        fs.copyFileSync(value, path.resolve(`./public/assets/js/${key}.min.js`));
    }
} else {
    for (const [key, value] of Object.entries(listOfJsFiles)) {
        minify({[value]: fs.readFileSync(value, 'utf8')}, options).then(({code}) => {
            fs.writeFileSync(path.resolve(`./public/assets/js/${key}.min.js`), code);
        });
    }
}

return 0;