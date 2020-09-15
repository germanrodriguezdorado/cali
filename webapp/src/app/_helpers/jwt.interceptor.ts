import { Injectable, Inject, Optional } from '@angular/core';
import { HttpInterceptor, HttpHandler, HttpRequest, HttpEvent } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable()
export class JwtInterceptor implements HttpInterceptor {
	constructor() { }

	intercept( request: HttpRequest<any>, next: HttpHandler ): Observable<HttpEvent<any>> {

		if (!request.url.startsWith('./assets')) {
			request = request.clone({
				setHeaders: {
					'Content-Type': 'application/json',
					'Accept': 'application/json',
					'Access-Control-Allow-Origin': '*'
				}
			});
		}

		const currentUser = JSON.parse(localStorage.getItem('currentUser'));
		if (currentUser && currentUser.token) {
			request = request.clone({
				setHeaders: {
					Authorization: "Bearer " +  currentUser.token
				}
			});
		}

		return next.handle(request);
	}
}