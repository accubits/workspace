import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UserlistResponseComponent } from './userlist-response.component';

describe('UserlistResponseComponent', () => {
  let component: UserlistResponseComponent;
  let fixture: ComponentFixture<UserlistResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UserlistResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UserlistResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
