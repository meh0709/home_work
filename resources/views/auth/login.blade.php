@extends('layouts.app')
@section('title', 'login')
@section('content')
    <main id="success-page">
        <section class="page-form">
            <div class="page-form__container">
                <div class="page-form__wrapper">
                    <form action="{{route('auth.login.user')}}" method="POST" class="page-form__info form">
                        @csrf
                        <h3 class="form__title">Вхід</h3>
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{session('error')}}
                            </div>
                        @endif
                        <div class="form__wrap">
                            <div class="form__item">
                                <input data-error="Введіть номер телефону" data-required="phone" data-validate name="phone" type="tel" placeholder="Введіть номер телефону" class="form__input phone-mask">
                            </div>
                        </div>
                        <button type="submit" class="form__button btn">Вхід</button>
                        <a href="{{route('auth.register')}}" class="form__link">Зареєструватися</a>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection
