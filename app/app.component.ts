import { Component } from '@angular/core';
import { HttpClient, HttpErrorResponse, HttpParams } from '@angular/common/http';
import { SESSION_STORAGE, StorageService, StorageTranscoders } from 'ngx-webstorage-service';
import { Inject, Injectable } from '@angular/core';
import { Book } from './book';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'profile';

  constructor(@Inject(SESSION_STORAGE) private storage: StorageService, private http: HttpClient)
  {
     //this.title = storage.get('user', StorageTranscoders.STRING);
  }

  booksRead = [];
  booksToRead = [];

  ngOnInit() {
  	this.http.post<any>('http://localhost/book-review-site/profile_load.php', null).subscribe((data) => {
  		this.booksRead = data['content'][0].booksRead;
  		this.booksToRead = data['content'][0].booksToRead;
  	});
  }

  book1 = new Book("harry potter", "jk rowling");

  user = "";

  getUser(form: any): void {
  	let params = JSON.stringify(form);

  	this.http.post<any>('http://localhost/book-review-site/navbar.php', params).subscribe((data) => {
	      this.user = data["content"][0].user;
	      console.log(data);
	      // save username to client-side session
	      sessionStorage.setItem('user', this.user);

	      // retrieve username from client-side session
	      //this.title = this.storage.get('user', StorageTranscoders.STRING);

	 }, (error) => {
          console.log('Error ', error);
     })
  }
  
}