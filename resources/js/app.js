import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('themeSwitcher', () => ({
        // currentTheme é para a UI do botão. O estado real está na classe <html>.
        currentTheme: 'light', // Valor inicial, será corrigido no init()

        init() {
            // Sincroniza o currentTheme do Alpine com o estado real do HTML
            // que foi definido pelo script inline no <head>
            this.currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';

            // Opcional: Ouvinte para mudanças na preferência do sistema
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('theme')) { // Só se não houver escolha manual
                    this.currentTheme = e.matches ? 'dark' : 'light';
                    this._updateHtmlClass(this.currentTheme); // Aplica sem salvar no localStorage
                }
            });
        },

        // Função interna para apenas atualizar a classe HTML e o estado do Alpine
        _updateHtmlClass(theme) {
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                this.currentTheme = 'dark';
            } else {
                document.documentElement.classList.remove('dark');
                this.currentTheme = 'light';
            }
        },

        // Função chamada pelo botão para alternar e salvar
        toggleTheme() {
            const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', newTheme); // Salva a escolha do usuário
            this._updateHtmlClass(newTheme); // Aplica a mudança
        }
    }));
});

Alpine.start();
