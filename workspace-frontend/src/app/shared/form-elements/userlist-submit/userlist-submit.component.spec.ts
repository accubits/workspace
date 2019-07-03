import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UserlistSubmitComponent } from './userlist-submit.component';

describe('UserlistSubmitComponent', () => {
  let component: UserlistSubmitComponent;
  let fixture: ComponentFixture<UserlistSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UserlistSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UserlistSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
