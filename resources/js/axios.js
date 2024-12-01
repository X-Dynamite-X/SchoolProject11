import axios from "axios"
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
 

// تعيين نوع الطلبات إلى JSON
axios.defaults.headers.common['Accept'] = 'application/json';

// ضبط عنوان قاعدة URL الخاص بالخادم
axios.defaults.baseURL = "http://192.168.1.204:8000";

// يمكنك إضافة Interceptors إذا لزم الأمر لاحقًا
