import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SecNewEmpComponent } from './sec-new-emp.component';

describe('SecNewEmpComponent', () => {
  let component: SecNewEmpComponent;
  let fixture: ComponentFixture<SecNewEmpComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SecNewEmpComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SecNewEmpComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
