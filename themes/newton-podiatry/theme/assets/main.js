import "./styles/main.scss";
import "./js/index";
import.meta.glob("../blocks/**/*.css", { eager: true });
import Alpine from "alpinejs";

window.Alpine = Alpine;

import.meta.glob("../blocks/**/*.js", { eager: true });

window.Alpine.start();
