import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DropdownResponseComponent } from './dropdown-response.component';

describe('DropdownResponseComponent', () => {
  let component: DropdownResponseComponent;
  let fixture: ComponentFixture<DropdownResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DropdownResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DropdownResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
