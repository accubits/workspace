import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EmpTableHeadComponent } from './emp-table-head.component';

describe('EmpTableHeadComponent', () => {
  let component: EmpTableHeadComponent;
  let fixture: ComponentFixture<EmpTableHeadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EmpTableHeadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EmpTableHeadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
