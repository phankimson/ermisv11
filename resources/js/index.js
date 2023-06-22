// Views
import CategoryView from './components/CategoryView'
import CategoryCreateView from './components/CategoryCreateView'
const locale = document.head.querySelector('meta[name="locale"]').content
export const routes = [
	{ path: '/'+locale+'/manage/test1', name: 'Category', component: CategoryView },
	{ path: '/'+locale+'/manage/test2', name: 'Create', component: CategoryCreateView }
];
