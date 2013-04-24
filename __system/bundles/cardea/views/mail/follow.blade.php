<p>{{Config::get('project.name')}}'da {{$user->full_name()}} sizi takip etmek istiyor.</p>
<p>Talepleri görüntüleyip işlem yapmak için <a href="{{url('users/followers')}}">buraya</a> tıklayın. Bağlantı çalışmıyorsa aşağıdaki adresi kopyalayıp tarayıcınızın adres çubuğuna yapıştırabilirsiniz.</p>
<p><a href="{{url('users/followers')}}">{{url('users/followers')}}</a></p>