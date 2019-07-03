import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SecFormComponent } from './sec-form.component';

describe('SecFormComponent', () => {
  let component: SecFormComponent;
  let fixture: ComponentFixture<SecFormComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SecFormComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SecFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
