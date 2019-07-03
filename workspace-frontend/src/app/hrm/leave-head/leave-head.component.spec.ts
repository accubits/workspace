import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LeaveHeadComponent } from './leave-head.component';

describe('LeaveHeadComponent', () => {
  let component: LeaveHeadComponent;
  let fixture: ComponentFixture<LeaveHeadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LeaveHeadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LeaveHeadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
