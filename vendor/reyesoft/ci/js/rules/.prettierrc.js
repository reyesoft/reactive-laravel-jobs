// .prettierrc.js
module.exports = {
    parser: 'typescript',
    printWidth: 140,
    proseWrap: 'never',
    singleQuote: true,
    tabWidth: 4,
    trailingComma: 'none',
    useTabs: false,
    // semicolons: true,
    overrides: [
		{
			files: '*.ts',
			options: {
                parser: 'typescript',
                // trailingComma: 'all',
			}
		},
		{
			files: '*.json',
			options: {
                parser: 'json',
                proseWrap: 'never',
                singleQuote: false
			}
		},
		{
			files: '*.md',
			options: {
                parser: 'markdown'
			}
		},
		{
			files: '*.{sass,scss}',
			options: {
                parser: 'scss'
			}
		}
	]
};
