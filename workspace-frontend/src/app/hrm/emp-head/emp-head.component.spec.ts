import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EmpHeadComponent } from './emp-head.component';

describe('EmpHeadComponent', () => {
  let component: EmpHeadComponent;
  let fixture: ComponentFixture<EmpHeadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EmpHeadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EmpHeadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
