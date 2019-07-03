import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LeaveNavBarComponent } from './leave-nav-bar.component';

describe('LeaveNavBarComponent', () => {
  let component: LeaveNavBarComponent;
  let fixture: ComponentFixture<LeaveNavBarComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LeaveNavBarComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LeaveNavBarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
