import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LeaveTypeDetailComponent } from './leave-type-detail.component';

describe('LeaveTypeDetailComponent', () => {
  let component: LeaveTypeDetailComponent;
  let fixture: ComponentFixture<LeaveTypeDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LeaveTypeDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LeaveTypeDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
