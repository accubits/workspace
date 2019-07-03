import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NameResponseComponent } from './name-response.component';

describe('NameResponseComponent', () => {
  let component: NameResponseComponent;
  let fixture: ComponentFixture<NameResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NameResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NameResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
